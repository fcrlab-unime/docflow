function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('religiousbelief') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('religiousbelief')
  span.classes:remove(position)
end
return span
end