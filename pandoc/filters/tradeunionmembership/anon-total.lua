function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('tradeunionmembership') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('tradeunionmembership')
  span.classes:remove(position)
end
return span
end