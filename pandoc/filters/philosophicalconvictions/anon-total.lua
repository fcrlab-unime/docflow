function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('philosophicalconvictions') then
  span.content = pandoc.Str('xxxxxx')
  local value, position = span.classes:find('philosophicalconvictions')
  span.classes:remove(position)
end
return span
end